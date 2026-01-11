/**
 * background.js
 * Background service worker for the extension.
 * Handles message passing and background tasks.
 */

// Import utils if using ES modules, or assume they are loaded in global scope if MV2
// For MV3 Service Worker, we use importScripts or ES modules.
try {
    importScripts('utils.js', 'extensionState.js', 'heuristicsRedefinitions.js');
} catch (e) {
    console.warn("Could not import scripts (might be running in a different context):", e);
}

/**
 * DelayedMessageSender
 * Handles sending messages with delays and error recovery.
 */
class DelayedMessageSender {
    constructor() {
        this.queue = [];
    }

    /**
     * Send a message to a specific tab and frame with error handling.
     * @param {number} tabId 
     * @param {number} frameId 
     * @param {any} message 
     * @param {number} delay 
     */
    async sendMessage(tabId, frameId, message, delay = 0) {
        if (delay > 0) {
            await new Promise(resolve => setTimeout(resolve, delay));
        }

        try {
            // FIX: Wrap sendMessage in a promise that handles chrome.runtime.lastError
            const response = await new Promise((resolve, reject) => {
                chrome.tabs.sendMessage(tabId, message, { frameId: frameId }, (res) => {
                    if (chrome.runtime.lastError) {
                        // Reject with the lastError to be caught below
                        reject(new Error(chrome.runtime.lastError.message));
                    } else {
                        resolve(res);
                    }
                });
            });
            
            console.log(`Message sent successfully to Tab ${tabId}, Frame ${frameId}`);
            return response;

        } catch (error) {
            this.handleError(error, tabId, frameId);
        }
    }

    /**
     * Handle errors specifically for message sending.
     * @param {Error} error 
     * @param {number} tabId 
     * @param {number} frameId 
     */
    handleError(error, tabId, frameId) {
        const msg = error.message || "";

        // FIX: Specifically handle FrameDoesNotExistError
        if (msg.includes("Frame") && msg.includes("does not exist")) {
            console.warn(`[Handled] Frame ${frameId} in Tab ${tabId} is gone. Ignoring message.`);
            return; // Gracefully exit, do not throw
        }

        // FIX: Handle "message port closed" error
        if (msg.includes("message port closed") || msg.includes("Receiving end does not exist")) {
            console.warn(`[Handled] Connection lost to Tab ${tabId}. The tab might have been closed or refreshed.`);
            return;
        }

        // Log other unexpected errors
        console.error("Unexpected error in DelayedMessageSender:", error);
    }
}

// Initialize the sender
const messageSender = new DelayedMessageSender();

// Example usage: Listen for connection or messages
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === "ping") {
        sendResponse({ status: "pong" });
    }
    // Ensure to return true if using async sendResponse
    return true; 
});

// Example of triggering the safe send (simulated)
// messageSender.sendMessage(123, 456, { type: "UPDATE" }, 100);
