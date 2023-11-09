import GcPdfViewer from "./GcPdfViewer";
import { ViewerOptions } from "./ViewerOptions";
import PdfReportPlugin from "./plugin";
declare type ViewerState = {
    settings: any;
    rotation: number;
    theme?: string | false;
    activePanelId?: string | null;
    isSidebarPinned: boolean;
    file?: string;
};
export declare class GcPdfViewerStateStorage {
    private _viewer;
    private _plugin;
    private _file?;
    private _fileRestored;
    constructor(_viewer: GcPdfViewer, _plugin: PdfReportPlugin);
    save(viewerStateArg?: Partial<ViewerState>): void;
    load(options: ViewerOptions, initialViewWasSet: boolean): void;
    onFileClosed(): void;
    onFileOpenByData(fileData: Uint8Array): void;
    onFileOpenByUrl(pdfUrl: string): void;
}
export {};
