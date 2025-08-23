import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');
const apiSuccessRate = new Rate('api_success');

// API test configuration
export const options = {
  stages: [
    { duration: '1m', target: 5 },   // Ramp up to 5 users
    { duration: '3m', target: 5 },   // Stay at 5 users
    { duration: '1m', target: 10 },  // Ramp up to 10 users
    { duration: '3m', target: 10 },  // Stay at 10 users
    { duration: '1m', target: 0 },   // Ramp down to 0 users
  ],
  thresholds: {
    http_req_duration: ['p(95)<2000'], // 95% of requests must complete below 2s
    http_req_failed: ['rate<0.1'],     // Error rate must be less than 10%
    errors: ['rate<0.1'],              // Custom error rate
    api_success: ['rate>0.9'],         // API success rate must be above 90%
  },
};

// Base URL
const BASE_URL = 'http://localhost:8000';

// Test data
const testUsers = [
  { email: 'admin@gmail.com', password: 'password' },
  { email: 'dev@tegar-aja.xyz', password: 'xxkenxyz' },
];

export default function () {
  // Random user selection
  const user = testUsers[Math.floor(Math.random() * testUsers.length)];
  
  // Test 1: Get CSRF token
  const csrfResponse = http.get(`${BASE_URL}/sanctum/csrf-cookie`);
  check(csrfResponse, {
    'CSRF cookie set': (r) => r.status === 204,
  });
  
  // Test 2: Admin login API
  const loginData = {
    email: user.email,
    password: user.password,
  };
  
  const loginResponse = http.post(`${BASE_URL}/login`, loginData, {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCSRFToken(csrfResponse),
    },
  });
  
  check(loginResponse, {
    'login API successful': (r) => r.status === 200 || r.status === 302,
    'login API response time OK': (r) => r.timings.duration < 2000,
  });
  
  // If login successful, test API endpoints
  if (loginResponse.status === 200 || loginResponse.status === 302) {
    // Test 3: Students API
    const studentsResponse = http.get(`${BASE_URL}/admin/students`, {
      headers: {
        'Accept': 'application/json',
      },
    });
    
    check(studentsResponse, {
      'students API status is 200': (r) => r.status === 200,
      'students API response time OK': (r) => r.timings.duration < 2000,
    });
    
    // Test 4: Exams API
    const examsResponse = http.get(`${BASE_URL}/admin/exams`, {
      headers: {
        'Accept': 'application/json',
      },
    });
    
    check(examsResponse, {
      'exams API status is 200': (r) => r.status === 200,
      'exams API response time OK': (r) => r.timings.duration < 2000,
    });
    
    // Test 5: Classrooms API
    const classroomsResponse = http.get(`${BASE_URL}/admin/classrooms`, {
      headers: {
        'Accept': 'application/json',
      },
    });
    
    check(classroomsResponse, {
      'classrooms API status is 200': (r) => r.status === 200,
      'classrooms API response time OK': (r) => r.timings.duration < 2000,
    });
    
    // Test 6: Lessons API
    const lessonsResponse = http.get(`${BASE_URL}/admin/lessons`, {
      headers: {
        'Accept': 'application/json',
      },
    });
    
    check(lessonsResponse, {
      'lessons API status is 200': (r) => r.status === 200,
      'lessons API response time OK': (r) => r.timings.duration < 2000,
    });
    
    // Test 7: Admins API
    const adminsResponse = http.get(`${BASE_URL}/admin/admins`, {
      headers: {
        'Accept': 'application/json',
      },
    });
    
    check(adminsResponse, {
      'admins API status is 200': (r) => r.status === 200,
      'admins API response time OK': (r) => r.timings.duration < 2000,
    });
    
    // Test 8: Reports API
    const reportsResponse = http.get(`${BASE_URL}/admin/reports`, {
      headers: {
        'Accept': 'application/json',
      },
    });
    
    check(reportsResponse, {
      'reports API status is 200': (r) => r.status === 200,
      'reports API response time OK': (r) => r.timings.duration < 3000,
    });
    
    // Test 9: Monitor API
    const monitorResponse = http.get(`${BASE_URL}/admin/monitor`, {
      headers: {
        'Accept': 'application/json',
      },
    });
    
    check(monitorResponse, {
      'monitor API status is 200': (r) => r.status === 200,
      'monitor API response time OK': (r) => r.timings.duration < 3000,
    });
    
    // Test 10: Settings API
    const settingsResponse = http.get(`${BASE_URL}/admin/settings`, {
      headers: {
        'Accept': 'application/json',
      },
    });
    
    check(settingsResponse, {
      'settings API status is 200': (r) => r.status === 200,
      'settings API response time OK': (r) => r.timings.duration < 2000,
    });
  }
  
  // Record metrics
  errorRate.add(loginResponse.status >= 400);
  apiSuccessRate.add(loginResponse.status < 400);
  
  // Sleep between iterations
  sleep(2);
}

// Helper function to extract CSRF token
function getCSRFToken(response) {
  const cookies = response.headers['Set-Cookie'];
  if (cookies) {
    const match = cookies.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : '';
  }
  return '';
}

// Setup function
export function setup() {
  console.log('Starting k6 API test for CBT Application');
  console.log(`Base URL: ${BASE_URL}`);
  console.log(`Test duration: ~9 minutes`);
  console.log(`Max concurrent users: 10`);
  console.log('This test focuses on API endpoints performance');
}

// Teardown function
export function teardown(data) {
  console.log('API test completed');
  console.log('Check the results for API performance insights');
} 