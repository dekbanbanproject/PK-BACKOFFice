import { PluginModel, DocumentMoniker } from '../api';
import { DocumentViewer } from "../components";
import { SearchResult } from '../features/search';
import { CancellationToken } from './CancellationToken';
export declare type LoadResult = {
    status: 'loaded';
    document: PluginModel.IDocument;
} | {
    status: 'error';
    message: string;
    details?: string;
} | {
    status: 'cancelled';
};
export declare class SessionState {
    readonly onChangeDocumentView: (view: PluginModel.IDocumentView | null) => void;
    readonly onChangeDocument: (view: PluginModel.IDocument | null) => void;
    readonly onDocumentProgress: (view: PluginModel.ProgressMessage) => void;
    readonly errorSink: PluginModel.IReportError;
    constructor(onChangeDocumentView: (view: PluginModel.IDocumentView | null) => void, onChangeDocument: (view: PluginModel.IDocument | null) => void, onDocumentProgress: (view: PluginModel.ProgressMessage) => void, errorSink: PluginModel.IReportError);
    private _plugin;
    private _document;
    private _documentView;
    private _viewState;
    private _cancel;
    private setDocumentView;
    readonly isDocumentOpened: boolean;
    readonly isRunning: boolean;
    readonly store: PluginModel.IStore<DocumentViewer.DocViewModel, DocumentViewer.DocViewMsg>;
    readonly document: PluginModel.IDocument | null;
    readonly documentView: PluginModel.IDocumentView | null;
    setPlugin(plugin: PluginModel.IPluginModule<PluginModel.ViewerEvent, any>): void;
    gotoPage: (pageIndex: number, doScroll?: boolean, scrollTo?: string | undefined) => Promise<void>;
    highlight: (result: SearchResult) => Promise<void>;
    cancel: (reason: any) => Promise<void>;
    refresh: () => void;
    updateView: () => void;
    run: () => void;
    load: (doc: DocumentMoniker, token?: CancellationToken | undefined) => Promise<LoadResult>;
    private cancellableRun;
    private processRun;
    private runImpl;
    private _lastFetchPagesCall;
    private _lastPageRequestIndex;
    fetchPages: ({ startPage, pageCount }: {
        startPage: number;
        pageCount: number;
    }) => Promise<void>;
}
