import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');
const recoveryRate = new Rate('recovery');

// Spike test configuration
export const options = {
  stages: [
    { duration: '2m', target: 10 },   // Normal load: 10 users
    { duration: '1m', target: 100 },  // Spike: 100 users (10x increase)
    { duration: '3m', target: 100 },  // Sustained spike: 100 users
    { duration: '1m', target: 10 },   // Recovery: back to 10 users
    { duration: '2m', target: 10 },   // Normal load: 10 users
  ],
  thresholds: {
    http_req_duration: ['p(95)<3000'], // 95% of requests must complete below 3s
    http_req_failed: ['rate<0.15'],     // Error rate must be less than 15%
    errors: ['rate<0.15'],              // Custom error rate
    recovery: ['rate>0.8'],             // Recovery rate must be above 80%
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
    'homepage handles spike': (r) => r.timings.duration < 3000,
  });
  
  // Test 2: Login page
  const loginPageResponse = http.get(`${BASE_URL}/login`);
  check(loginPageResponse, {
    'login page status is 200': (r) => r.status === 200,
    'login page handles spike': (r) => r.timings.duration < 3000,
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
    'login successful during spike': (r) => r.status === 200 || r.status === 302,
    'login response time during spike': (r) => r.timings.duration < 4000,
  });
  
  // If login successful, test admin pages
  if (loginResponse.status === 200 || loginResponse.status === 302) {
    // Test 4: Dashboard
    const dashboardResponse = http.get(`${BASE_URL}/admin/dashboard`);
    check(dashboardResponse, {
      'dashboard status is 200 during spike': (r) => r.status === 200,
      'dashboard handles spike': (r) => r.timings.duration < 4000,
    });
    
    // Test 5: Students page
    const studentsResponse = http.get(`${BASE_URL}/admin/students`);
    check(studentsResponse, {
      'students page status is 200 during spike': (r) => r.status === 200,
      'students page handles spike': (r) => r.timings.duration < 4000,
    });
    
    // Test 6: Proctoring pages (new features)
    const proctoringPages = [
      '/admin/proctoring/sessions',
      '/admin/proctoring/violations',
      '/admin/proctoring/photos',
      '/admin/proctoring/activities',
      '/admin/proctoring/network',
      '/admin/proctoring/settings',
    ];
    
    // Test 2-3 random proctoring pages
    const selectedPages = proctoringPages
      .sort(() => 0.5 - Math.random())
      .slice(0, Math.floor(Math.random() * 2) + 2);
    
    selectedPages.forEach(page => {
      const response = http.get(`${BASE_URL}${page}`);
      check(response, {
        [`${page} status is 200 during spike`]: (r) => r.status === 200,
        [`${page} handles spike`]: (r) => r.timings.duration < 5000,
      });
    });
  }
  
  // Record metrics
  errorRate.add(loginResponse.status >= 400);
  recoveryRate.add(loginResponse.status < 400);
  
  // Sleep between iterations
  sleep(1);
}

// Helper function to extract CSRF token
function getCSRFToken(response) {
  const body = response.body;
  const match = body.match(/name="_token" value="([^"]+)"/);
  return match ? match[1] : '';
}

// Setup function
export function setup() {
  console.log('Starting k6 spike test for CBT Application');
  console.log(`Base URL: ${BASE_URL}`);
  console.log(`Test duration: ~9 minutes`);
  console.log(`Normal load: 10 users`);
  console.log(`Spike load: 100 users (10x increase)`);
  console.log('This test simulates sudden traffic spikes');
}

// Teardown function
export function teardown(data) {
  console.log('Spike test completed');
  console.log('Check the results for spike handling and recovery');
} 