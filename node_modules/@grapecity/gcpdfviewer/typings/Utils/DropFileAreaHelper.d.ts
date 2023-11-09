export declare class DropFileAreaHelper {
    private _dropArea;
    private _viewer;
    private _onFileDrop;
    private _onImageUrlDrop;
    private _handlers;
    private _activated;
    constructor(_dropArea: HTMLElement, _viewer: any, _onFileDrop: (file: any, point: any) => void, _onImageUrlDrop: (imgUrl: any, point: any) => void);
    on(): void;
    off(): void;
    private _registerEvents;
    private _unregisterEvents;
    private _dragEnter;
    private _dragOver;
    private _dragLeave;
    private _drop;
    private _handleFiles;
    static getCanvasSize(canvas?: HTMLCanvasElement): {
        h: number;
        w: number;
    };
    static loadImageFromUrl(url: string): Promise<HTMLImageElement | null>;
    static imageToBytes(img: HTMLImageElement): Promise<Uint8Array | null>;
    static canvasToImageData(canvas?: HTMLCanvasElement): Promise<Uint8Array | null>;
}
