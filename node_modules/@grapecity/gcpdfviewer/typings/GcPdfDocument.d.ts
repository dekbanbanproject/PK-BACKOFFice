//@ts-ignore
import { PluginModel } from "@grapecity/viewer-core";
import { IHttpClient } from './httpClient';
import { GcPdfSearcher } from './Search/GcPdfSearcher';
import { GcPdfPrintService } from './Print/GcPdfPrintService';
import { FileAttachmentAnnotation } from "./Annotations/AnnotationTypes";
import GcPdfViewer from ".";
import { OpenParameters } from "./Models/ViewerTypes";
export declare type ParameterInfo = {
    Name: string;
    Omit?: boolean;
    Value: string;
};
export declare type InteractivityAction = {
    Type: 'toggle';
    Data: string;
} | {
    Type: 'sort';
    Data: string;
};
export declare type DrillthroughAction = {
    Type: 'drillthrough';
    Target: string;
    Parameters: ParameterInfo[];
};
export declare class GcPdfDocument implements PluginModel.IDocument {
    readonly viewer: GcPdfViewer;
    readonly docViewer: any;
    readonly fileDataOrUrl: any;
    readonly http: IHttpClient;
    readonly _searcher: GcPdfSearcher;
    readonly _openParameters?: OpenParameters | undefined;
    private _attachmentsStore;
    private _pdfDocument;
    private _interactivityActions;
    private _documentView;
    private _printService;
    private _openDocPromise;
    private _sink;
    constructor(viewer: GcPdfViewer, docViewer: any, fileDataOrUrl: any, http: IHttpClient, _searcher: GcPdfSearcher, _openParameters?: OpenParameters | undefined);
    cleanup(): Promise<void>;
    canView: () => boolean;
    get fileData(): Uint8Array;
    storeInteractivity: (it: InteractivityAction[]) => number;
    get storeI11yPos(): number;
    truncateInteractivityStore(pos: number): void;
    newPage(params?: {
        width?: number;
        height?: number;
        pageIndex?: number;
    }): Promise<void>;
    deletePage(pageIndex?: number): Promise<void>;
    raiseDocumentViewComplete(): void;
    createView: (baseView: PluginModel.IDocumentView | null, sink: PluginModel.IRunEventsSink) => Promise<PluginModel.IDocumentView | undefined>;
    get attachmentsStore(): any;
    updateView: (view: PluginModel.IDocumentView, sink: PluginModel.IRunEventsSink) => Promise<PluginModel.IDocumentView>;
    get printService(): GcPdfPrintService;
    print(): void;
    private loadAttachments;
    setAttachments(attachments: FileAttachmentAnnotation[]): void;
    appendAttachment(attachment: FileAttachmentAnnotation): void;
    private getArticles;
    private getOutline;
    private _fillOutlineTree;
}
