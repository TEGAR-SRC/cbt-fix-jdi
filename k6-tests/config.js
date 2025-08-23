// Configuration file for K6 tests
export const config = {
  // Base configuration
  baseUrl: 'http://localhost:8000',
  
  // Test users
  testUsers: [
    { email: 'admin@gmail.com', password: 'password' },
    { email: 'dev@tegar-aja.xyz', password: 'xxkenxyz' },
  ],
  
  // Thresholds
  thresholds: {
    load: {
      http_req_duration: ['p(95)<2000'],
      http_req_failed: ['rate<0.1'],
      errors: ['rate<0.1'],
    },
    stress: {
      http_req_duration: ['p(95)<5000'],
      http_req_failed: ['rate<0.2'],
      errors: ['rate<0.2'],
      success: ['rate>0.8'],
    },
    spike: {
      http_req_duration: ['p(95)<3000'],
      http_req_failed: ['rate<0.15'],
      errors: ['rate<0.15'],
      recovery: ['rate>0.8'],
    },
    api: {
      http_req_duration: ['p(95)<2000'],
      http_req_failed: ['rate<0.1'],
      errors: ['rate<0.1'],
      api_success: ['rate>0.9'],
    },
  },
  
  // Test stages
  stages: {
    load: [
      { duration: '2m', target: 10 },
      { duration: '5m', target: 10 },
      { duration: '2m', target: 20 },
      { duration: '5m', target: 20 },
      { duration: '2m', target: 0 },
    ],
    stress: [
      { duration: '2m', target: 50 },
      { duration: '5m', target: 50 },
      { duration: '2m', target: 100 },
      { duration: '5m', target: 100 },
      { duration: '2m', target: 150 },
      { duration: '5m', target: 150 },
      { duration: '2m', target: 0 },
    ],
    spike: [
      { duration: '2m', target: 10 },
      { duration: '1m', target: 100 },
      { duration: '3m', target: 100 },
      { duration: '1m', target: 10 },
      { duration: '2m', target: 10 },
    ],
    api: [
      { duration: '1m', target: 5 },
      { duration: '3m', target: 5 },
      { duration: '1m', target: 10 },
      { duration: '3m', target: 10 },
      { duration: '1m', target: 0 },
    ],
  },
  
  // Test endpoints
  endpoints: {
    public: [
      '/',
      '/login',
    ],
    admin: [
      '/admin/dashboard',
      '/admin/students',
      '/admin/exams',
      '/admin/classrooms',
      '/admin/lessons',
      '/admin/admins',
      '/admin/guardians',
      '/admin/assignments',
      '/admin/tryouts',
      '/admin/exam_sessions',
      '/admin/reports',
      '/admin/monitor',
      '/admin/settings',
    ],
    proctoring: [
      '/admin/proctoring/sessions',
      '/admin/proctoring/violations',
      '/admin/proctoring/photos',
      '/admin/proctoring/activities',
      '/admin/proctoring/network',
      '/admin/proctoring/settings',
    ],
    api: [
      '/admin/students',
      '/admin/exams',
      '/admin/classrooms',
      '/admin/lessons',
      '/admin/admins',
      '/admin/reports',
      '/admin/monitor',
      '/admin/settings',
    ],
  },
  
  // Headers
  headers: {
    default: {
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
      'Accept-Language': 'en-US,en;q=0.5',
      'Accept-Encoding': 'gzip, deflate',
      'Connection': 'keep-alive',
      'Upgrade-Insecure-Requests': '1',
    },
    api: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
  },
  
  // Timeouts
  timeouts: {
    request: '30s',
    response: '30s',
  },
  
  // Sleep intervals
  sleep: {
    load: 1,
    stress: 0.5,
    spike: 1,
    api: 2,
  },
  
  // Custom metrics
  metrics: {
    errorRate: 'errors',
    successRate: 'success',
    recoveryRate: 'recovery',
    apiSuccessRate: 'api_success',
  },
};

// Helper functions
export const helpers = {
  // Get random user
  getRandomUser() {
    return config.testUsers[Math.floor(Math.random() * config.testUsers.length)];
  },
  
  // Get random endpoint from category
  getRandomEndpoint(category) {
    const endpoints = config.endpoints[category];
    return endpoints[Math.floor(Math.random() * endpoints.length)];
  },
  
  // Get CSRF token from response
  getCSRFToken(response) {
    const body = response.body;
    const match = body.match(/name="_token" value="([^"]+)"/);
    return match ? match[1] : '';
  },
  
  // Get CSRF token from cookies
  getCSRFTokenFromCookies(response) {
    const cookies = response.headers['Set-Cookie'];
    if (cookies) {
      const match = cookies.match(/XSRF-TOKEN=([^;]+)/);
      return match ? decodeURIComponent(match[1]) : '';
    }
    return '';
  },
  
  // Create login data
  createLoginData(user) {
    return {
      email: user.email,
      password: user.password,
    };
  },
  
  // Create headers with CSRF token
  createHeadersWithCSRF(csrfToken, type = 'default') {
    return {
      ...config.headers[type],
      'X-CSRF-TOKEN': csrfToken,
    };
  },
  
  // Log test info
  logTestInfo(testName, baseUrl, duration, maxUsers) {
    console.log(`Starting ${testName} for CBT Application`);
    console.log(`Base URL: ${baseUrl}`);
    console.log(`Test duration: ~${duration} minutes`);
    console.log(`Max concurrent users: ${maxUsers}`);
  },
  
  // Log test completion
  logTestCompletion(testName) {
    console.log(`${testName} completed`);
    console.log('Check the results for performance insights');
  },
};

// Export default config
export default config; 