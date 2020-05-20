// jest.config.js
module.exports = {
    testRegex: 'resources_backend/client_app/modules/presentation/tests/.*.spec.js$',
    moduleNameMapper: {
      "^@/(.*)$": "<rootDir>/resources_backend/client_app/modules/presentation/$1"
    },
    moduleFileExtensions: ['js', 'json', 'vue'],
    transform: {
      '^.+\\.js$': '<rootDir>/node_modules/babel-jest',
      '.*\\.(vue)$': '<rootDir>/node_modules/vue-jest'
    },
    snapshotSerializers: ['jest-serializer-vue'],
    collectCoverageFrom: [
      'resources_backend/client_app/modules/presentation/**/*.{js,jsx,ts,tsx,vue}',
    ],
    collectCoverage: false,
    coverageReporters: ['html', 'lcov', 'text-summary'],
    coverageDirectory: './coverage'
  }