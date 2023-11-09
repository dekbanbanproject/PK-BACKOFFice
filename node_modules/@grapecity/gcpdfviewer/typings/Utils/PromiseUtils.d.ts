export declare function createPromiseCapability(): {
    promise: Promise<any>;
    resolve: Function;
    reject: Function;
};
export declare function createPromiseCapabilityWithTimeout(rejectTimeout?: number, timeoutMessage?: string): {
    promise: Promise<any>;
    resolve: Function;
    reject: Function;
};
