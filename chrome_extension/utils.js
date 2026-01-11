/**
 * utils.js
 * Common utility functions for the extension.
 */

const Utils = {
    /**
     * Log messages with a timestamp.
     * @param {string} message 
     */
    log: function(message) {
        console.log(`[${new Date().toISOString()}] ${message}`);
    },

    /**
     * Check if a variable is defined and not null.
     * @param {any} value 
     * @returns {boolean}
     */
    isDefined: function(value) {
        return typeof value !== 'undefined' && value !== null;
    }
};

// Export for usage if using modules, or attach to window/global
if (typeof window !== 'undefined') {
    window.Utils = Utils;
}
