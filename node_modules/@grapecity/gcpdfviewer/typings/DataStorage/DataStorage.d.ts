export declare class DataStorage {
    owner: any;
    private _data;
    private _images;
    constructor(owner: any);
    collectGarbage(): void;
    setImageDataFromUrl(imageFileName: string, cacheKey: string, resourceUrl: string): Promise<boolean>;
    createImage(imageFileName: string, cacheKey: string): HTMLImageElement | null;
    setImageSrc(img: HTMLImageElement, imageFileName: string): boolean;
    dispose(): void;
    releaseResources(): void;
    setItem(keyName: string, keyValue: Uint8Array | any): void;
    getItem(keyName: string | undefined): Uint8Array | any | null;
    hasItem(keyName: string | undefined): boolean;
    removeItem(keyName: string | undefined): void;
    releaseImage(keyName: string): void;
    getEncodedItem(keyName: string): string | null;
    ensureNewImageFileName(fileName: string, extension?: string): string;
}
