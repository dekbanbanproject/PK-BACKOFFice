export declare class BackgroundUtilsWorker {
    private _worker;
    constructor();
    dispose(): void;
    convertBytesToBase64(bytes: Uint8Array): Promise<string>;
}
