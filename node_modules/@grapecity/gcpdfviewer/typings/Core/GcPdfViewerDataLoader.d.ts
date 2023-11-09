import GcPdfViewer from "../GcPdfViewer";
import { OptionalContentConfig } from "../Models/ViewerTypes";
export declare type ViewerMetaDataType = "optionalContentConfig" | "structureTree";
export declare class GcPdfViewerDataLoader {
    viewer: GcPdfViewer;
    private _dataPromise;
    private _data;
    private _listeners;
    private _documentOpenedPromise1;
    constructor(viewer: GcPdfViewer);
    getDataPromise(key: ViewerMetaDataType): Promise<OptionalContentConfig>;
    getData(key: ViewerMetaDataType): OptionalContentConfig | null;
    listen(key: ViewerMetaDataType, callback: (data: any, type: "data" | "reset") => void): void;
    unlisten(key: ViewerMetaDataType): void;
    get documentOpenedPromise(): {
        promise: Promise<any>;
        resolve: Function;
        reject: Function;
    };
    createDocumentOpenedPromiseCap(recreate: boolean): void;
    onCleanupDocument(): void;
    onDocumentOpened(): void;
    notify(key: ViewerMetaDataType, data: any, type?: "data" | "reset"): void;
}
