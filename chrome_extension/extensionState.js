/**
 * extensionState.js
 * Manages the global state of the extension.
 */

const ExtensionState = {
    state: {
        isActive: true,
        lastUpdate: null,
        connectedTabs: new Set()
    },

    /**
     * Initialize the state.
     */
    init: function() {
        this.state.lastUpdate = Date.now();
        console.log("Extension State Initialized");
    },

    /**
     * Update a specific state key.
     * @param {string} key 
     * @param {any} value 
     */
    update: function(key, value) {
        this.state[key] = value;
        this.state.lastUpdate = Date.now();
    }
};

ExtensionState.init();
