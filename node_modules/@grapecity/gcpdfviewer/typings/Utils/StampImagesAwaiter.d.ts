import PdfReportPlugin from "../plugin";
export declare class StampImagesAwaiter {
    private plugin;
    private _loadedImagesInfoHash;
    constructor(plugin: PdfReportPlugin);
    onAnnotationImageLoaded(info: {
        id: string;
        fileId: string;
        fileName: string;
        fileLength: number;
    }): void;
    private _applyLoadedImagesInfo;
    private setStampAnnotationFile;
}
