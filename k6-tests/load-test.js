import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');

// Test configuration
export const options = {
  stages: [
    { duration: '2m', target: 10 }, // Ramp up to 10 users
    { duration: '5m', target: 10 }, // Stay at 10 users for 5 minutes
    { duration: '2m', target: 20 }, // Ramp up to 20 users
    { duration: '5m', target: 20 }, // Stay at 20 users for 5 minutes
    { duration: '2m', target: 0 },  // Ramp down to 0 users
  ],
  thresholds: {
    http_req_duration: ['p(95)<2000'], // 95% of requests must complete below 2s
    http_req_failed: ['rate<0.1'],     // Error rate must be less than 10%
    errors: ['rate<0.1'],              // Custom error rate
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
  
  // Test 1: Homepage
  const homeResponse = http.get(`${BASE_URL}/`);
  check(homeResponse, {
    'homepage status is 200': (r) => r.status === 200,
    'homepage loads fast': (r) => r.timings.duration < 1000,
  });
  
  // Test 2: Login page
  const loginPageResponse = http.get(`${BASE_URL}/login`);
  check(loginPageResponse, {
    'login page status is 200': (r) => r.status === 200,
    'login page loads fast': (r) => r.timings.duration < 1000,
  });
  
  // Test 3: Admin login
  const loginData = {
    email: user.email,
    password: user.password,
  };
  
  const loginResponse = http.post(`${BASE_URL}/login`, loginData, {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCSRFToken(loginPageResponse),
    },
  });
  
  check(loginResponse, {
    'login successful': (r) => r.status === 200 || r.status === 302,
    'login response time OK': (r) => r.timings.duration < 2000,
  });
  
  // If login successful, test admin pages
  if (loginResponse.status === 200 || loginResponse.status === 302) {
    // Test 4: Dashboard
    const dashboardResponse = http.get(`${BASE_URL}/admin/dashboard`);
    check(dashboardResponse, {
      'dashboard status is 200': (r) => r.status === 200,
      'dashboard loads fast': (r) => r.timings.duration < 1500,
    });
    
    // Test 5: Students page
    const studentsResponse = http.get(`${BASE_URL}/admin/students`);
    check(studentsResponse, {
      'students page status is 200': (r) => r.status === 200,
      'students page loads fast': (r) => r.timings.duration < 1500,
    });
    
    // Test 6: Exams page
    const examsResponse = http.get(`${BASE_URL}/admin/exams`);
    check(examsResponse, {
      'exams page status is 200': (r) => r.status === 200,
      'exams page loads fast': (r) => r.timings.duration < 1500,
    });
    
    // Test 7: Proctoring pages
    const proctoringPages = [
      '/admin/proctoring/sessions',
      '/admin/proctoring/violations',
      '/admin/proctoring/photos',
      '/admin/proctoring/activities',
      '/admin/proctoring/network',
      '/admin/proctoring/settings',
    ];
    
    proctoringPages.forEach(page => {
      const response = http.get(`${BASE_URL}${page}`);
      check(response, {
        [`${page} status is 200`]: (r) => r.status === 200,
        [`${page} loads fast`]: (r) => r.timings.duration < 2000,
      });
    });
  }
  
  // Record errors
  errorRate.add(loginResponse.status >= 400);
  
  // Sleep between iterations
  sleep(1);
}

// Helper function to extract CSRF token
function getCSRFToken(response) {
  const body = response.body;
  const match = body.match(/name="_token" value="([^"]+)"/);
  return match ? match[1] : '';
}

// Setup function (runs once at the beginning)
export function setup() {
  console.log('Starting k6 load test for CBT Application');
  console.log(`Base URL: ${BASE_URL}`);
  console.log(`Test duration: ~16 minutes`);
  console.log(`Max concurrent users: 20`);
}

// Teardown function (runs once at the end)
export function teardown(data) {
  console.log('Load test completed');
  console.log('Check the results for performance insights');
} 