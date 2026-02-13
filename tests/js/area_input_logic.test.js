// Unit tests for Area Input Logic
// Run with: node tests/js/area_input_logic.test.js

const handleAreaInputLogic = (value) => {
    // Logic extracted from frontend_dashboard_add_listing.blade.php
    if (/^0[0-9]/.test(value)) {
        return value.replace(/^0+/, '');
    }
    return value;
};

const runTests = () => {
    const testCases = [
        { input: '05', expected: '5', description: 'Strip leading zero from 05' },
        { input: '0078', expected: '78', description: 'Strip multiple leading zeros from 0078' },
        { input: '0', expected: '0', description: 'Keep single 0' },
        { input: '100', expected: '100', description: 'Keep normal number 100' },
        { input: '0.5', expected: '0.5', description: 'Keep decimal starting with 0' },
        { input: '00.5', expected: '.5', description: 'Strip leading zeros from 00.5' },
        { input: '', expected: '', description: 'Empty input stays empty' },
        { input: '000', expected: '', description: 'Multiple zeros become empty (strip all leading)' }
    ];

    let passed = 0;
    let failed = 0;

    console.log('Running Area Input Logic Tests...\n');

    testCases.forEach(test => {
        const result = handleAreaInputLogic(test.input);
        if (result === test.expected) {
            console.log(`✅ PASS: ${test.description} ('${test.input}' -> '${result}')`);
            passed++;
        } else {
            console.error(`❌ FAIL: ${test.description}`);
            console.error(`   Expected: '${test.expected}'`);
            console.error(`   Actual:   '${result}'`);
            failed++;
        }
    });

    console.log(`\nResults: ${passed} passed, ${failed} failed.`);
    
    if (failed > 0) process.exit(1);
};

runTests();
