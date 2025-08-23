import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';

// Custom metrics
const errorRate = new Rate('errors');
const successRate = new Rate('success');

// Stress test configuration
export const options = {
  stages: [
    { duration: '2m', target: 50 },   // Ramp up to 50 users
    { duration: '5m', target: 50 },   // Stay at 50 users
    { duration: '2m', target: 100 },  // Ramp up to 100 users
    { duration: '5m', target: 100 },  // Stay at 100 users
    { duration: '2m', target: 150 },  // Ramp up to 150 users
    { duration: '5m', target: 150 },  // Stay at 150 users
    { duration: '2m', target: 0 },    // Ramp down to 0 users
  ],
  thresholds: {
    http_req_duration: ['p(95)<5000'], // 95% of requests must complete below 5s
    http_req_failed: ['rate<0.2'],     // Error rate must be less than 20%
    errors: ['rate<0.2'],              // Custom error rate
    success: ['rate>0.8'],             // Success rate must be above 80%
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
  
  // Test 1: Homepage (high load)
  const homeResponse = http.get(`${BASE_URL}/`);
  check(homeResponse, {
    'homepage status is 200': (r) => r.status === 200,
    'homepage loads under stress': (r) => r.timings.duration < 3000,
  });
  
  // Test 2: Login page
  const loginPageResponse = http.get(`${BASE_URL}/login`);
  check(loginPageResponse, {
    'login page status is 200': (r) => r.status === 200,
    'login page loads under stress': (r) => r.timings.duration < 3000,
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
    'login successful under stress': (r) => r.status === 200 || r.status === 302,
    'login response time under stress': (r) => r.timings.duration < 4000,
  });
  
  // If login successful, test admin pages under stress
  if (loginResponse.status === 200 || loginResponse.status === 302) {
    // Test 4: Dashboard (heavy page)
    const dashboardResponse = http.get(`${BASE_URL}/admin/dashboard`);
    check(dashboardResponse, {
      'dashboard status is 200 under stress': (r) => r.status === 200,
      'dashboard loads under stress': (r) => r.timings.duration < 4000,
    });
    
    // Test 5: Students page (database heavy)
    const studentsResponse = http.get(`${BASE_URL}/admin/students`);
    check(studentsResponse, {
      'students page status is 200 under stress': (r) => r.status === 200,
      'students page loads under stress': (r) => r.timings.duration < 4000,
    });
    
    // Test 6: Exams page (complex queries)
    const examsResponse = http.get(`${BASE_URL}/admin/exams`);
    check(examsResponse, {
      'exams page status is 200 under stress': (r) => r.status === 200,
      'exams page loads under stress': (r) => r.timings.duration < 4000,
    });
    
    // Test 7: Proctoring pages (new features)
    const proctoringPages = [
      '/admin/proctoring/sessions',
      '/admin/proctoring/violations',
      '/admin/proctoring/photos',
      '/admin/proctoring/activities',
      '/admin/proctoring/network',
      '/admin/proctoring/settings',
    ];
    
    // Randomly select 2-3 proctoring pages to test
    const selectedPages = proctoringPages
      .sort(() => 0.5 - Math.random())
      .slice(0, Math.floor(Math.random() * 3) + 2);
    
    selectedPages.forEach(page => {
      const response = http.get(`${BASE_URL}${page}`);
      check(response, {
        [`${page} status is 200 under stress`]: (r) => r.status === 200,
        [`${page} loads under stress`]: (r) => r.timings.duration < 5000,
      });
    });
  }
  
  // Record metrics
  errorRate.add(loginResponse.status >= 400);
  successRate.add(loginResponse.status < 400);
  
  // Sleep between iterations (shorter for stress test)
  sleep(0.5);
}

// Helper function to extract CSRF token
function getCSRFToken(response) {
  const body = response.body;
  const match = body.match(/name="_token" value="([^"]+)"/);
  return match ? match[1] : '';
}

// Setup function
export function setup() {
  console.log('Starting k6 stress test for CBT Application');
  console.log(`Base URL: ${BASE_URL}`);
  console.log(`Test duration: ~21 minutes`);
  console.log(`Max concurrent users: 150`);
  console.log('This test will push the application to its limits');
}

// Teardown function
export function teardown(data) {
  console.log('Stress test completed');
  console.log('Check the results for breaking points and performance degradation');
} 