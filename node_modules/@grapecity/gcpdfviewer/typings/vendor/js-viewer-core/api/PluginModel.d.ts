import { CancellationToken, SessionState } from '../control';
import { ReportViewerCmd, ReportViewerCommandStatus, DocumentMoniker } from './ReportViewerCmd';
import { FindOptions, SearchResult } from '../features/search';
//@ts-ignore
import { Icon } from '@grapecity/core-ui';
export declare type ChangeHandler<TState> = (state: TState) => void;
export interface IStore<TState, TMsg> {
    dispatch(msg: TMsg): void;
    readonly state: TState;
    subscribe(onUpdate: ChangeHandler<TState>): () => void;
}
export interface IStateBinder<TState> {
    bind(store: IStore<TState, any>): void;
}
export declare type PanelHandle = {
    id: string;
};
export declare type TaskCallbacks = {
    onProgress: (message: any) => void;
    isCancelRequested: () => boolean;
};
export declare type TaskSettings = {
    title?: string;
    supportCancel?: boolean;
};
export declare type ErrorSeverity = "error" | "warn" | "info" | "debug";
export declare type ErrorMessage = {
    severity: ErrorSeverity;
    message: string;
    details: string;
};
export declare type ErrorHandler = (error: ErrorMessage) => boolean;
export interface IErrorParams {
    readonly severity?: ErrorSeverity;
    readonly message: string;
    readonly details?: string;
}
export interface IViewerEvent {
    readonly type: string;
}
export declare type ViewerEvent = IViewerEvent & {
    type: "jumpedToPage";
    fromPage: number | null;
    to: {
        pageIndex: number;
        selector?: string;
    };
} | {
    type: "drilledToDocument";
    fromDoc: SessionState;
    target: DocumentMoniker;
};
export declare type ActionContext<TEvent extends IViewerEvent> = {
    readonly document: IDocument;
    readonly view: IDocumentView | null;
    processCommand(cmd: ReportViewerCmd): void;
    processAction(action: ViewerAction): void;
    pushEvent(event: TEvent): void;
};
export declare type ViewerAction = {
    Type: "goBookmark";
    Target: {
        pageNumber: number;
        selector?: string;
    };
} | {
    Type: "goHyperlink";
    Target: string;
} | {
    Type: "drillDocument";
    Target: DocumentMoniker;
};
export interface IReportError {
    reportError(params: IErrorParams): void;
}
export declare class PluginError extends Error {
    readonly text: string;
    readonly details: string;
    constructor(title: string, text: string, details: string);
}
export declare class DocumentError extends PluginError {
    constructor(text: string, details: string);
}
export declare type PanelSettings = {
    label: string;
    icon: string | Icon;
    description: string;
    visible: boolean;
    enabled: boolean;
};
export interface IViewerHost extends IReportError {
    createPanel<T>(component: any, binder: IStateBinder<T>, key: string, settings: Partial<PanelSettings>): PanelHandle;
    showPanel(panelKey: PanelHandle, visible?: boolean): void;
    updatePanel(panelKey: PanelHandle, settings: Partial<PanelSettings>): void;
    bringPanelToFront(panel: PanelHandle): void;
    bindPanel<TState, TMsg>(panel: PanelHandle, store: IStore<TState, TMsg>): void;
    closePanelOnNarrowScreen(panel?: PanelHandle): void;
    processCommand(cmd: ReportViewerCmd): void;
    processAction(action: ViewerAction): void;
    readonly commandStatus: ReportViewerCommandStatus;
}
export declare enum TimeDirection {
    Backward = "backward",
    Forward = "forward"
}
export interface IPluginModule<TEvent extends IViewerEvent, TAction> {
    readonly pluginKey: string;
    openDocument(location: DocumentMoniker, token?: CancellationToken): Promise<IDocument | null>;
    renderPage(page: IPageData): PageView;
    renderHighlightPage?(page: IPageData, results: SearchResult[]): PageView;
    onOpenDocument?(view: IDocument | null): void;
    onOpenDocumentView(view: IDocumentView | null): void;
    resolveAction(context: ActionContext<TEvent>, event: MouseEvent): TAction | ViewerAction | null;
    processAction(context: ActionContext<TEvent>, action: TAction, processCommand: (cmd: ReportViewerCmd) => void): boolean;
    processEvent(context: ActionContext<TEvent>, event: TEvent, direction: TimeDirection): void;
}
export declare type PageCountResult = {
    totalPageCount: number | null;
    renderedSoFar: number;
};
export declare type ProgressMessage = {
    phase: "starting";
    message: string;
} | {
    phase: "run";
    document?: IDocumentView;
    count: PageCountResult;
} | {
    phase: "complete";
    document?: IDocumentView;
    pageCount: number;
} | {
    phase: "cancelled";
};
export interface IRunEventsSink extends IReportError {
    progress(message: ProgressMessage): Promise<void>;
    invalidatePage(index: number, count?: number): void;
    readonly cancel: CancellationToken;
}
export interface IDocument {
    canView(): boolean;
    createView(baseView: IDocumentView | null, sink: IRunEventsSink): Promise<IDocumentView | undefined>;
    updateView(view: IDocumentView, sink: IRunEventsSink): Promise<IDocumentView | undefined>;
}
export interface IDocumentView {
    readonly pageCount: PageCountResult;
    awaitPage(index: number): Promise<IPageData | null>;
//@ts-ignore
    search?(options: FindOptions, startFrom: SearchResult): AsyncIterableIterator<SearchResult>;
}
export declare type PageSize = {
    width: string;
    height: string;
};
export interface IPageData {
    readonly pageSize: PageSize;
}
export declare type PageView = {
    kind: 'html';
    html: string;
} | {
    kind: 'dom';
    dom: Element;
};
