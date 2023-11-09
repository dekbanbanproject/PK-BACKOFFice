export declare type HistoryDir = "Back" | "Fwd" | "Parent";
export declare type GotoTarget = "first" | "prev" | "next" | "last";
export interface DocumentMoniker {
    readonly pluginKey: string;
}
export declare type ReportViewerCmd = {
    type: "Goto";
    payload: {
        target: GotoTarget;
    };
} | {
    type: "GotoPage";
    payload: {
        pageIndex: number;
        scrollTo?: string;
    };
} | {
    type: "SetCurrentPage";
    payload: {
        pageIndex: number;
    };
} | {
    type: "FetchPages";
    payload: {
        startPage: number;
        pageCount: number;
    };
} | {
    type: "Refresh";
} | {
    type: "Run";
} | {
    type: "Cancel";
} | {
    type: "ClearHistory";
    payload: {
        hard?: boolean;
    };
} | {
    type: "GoHistory";
    payload: {
        direction: HistoryDir;
    };
} | {
    type: "UpdateView";
} | {
    type: "OpenDocument";
    payload: DocumentMoniker;
};
export declare type ReportViewerCommandStatus = {
    readonly canGoBack: boolean;
    readonly canGoForward: boolean;
    readonly canRefresh: boolean;
    readonly canCancel: boolean;
    readonly canGoHistoryBack: boolean;
    readonly canGoBackToParent: boolean;
    readonly canGoHistoryForward: boolean;
    readonly canGoHistory: {
        Back: boolean;
        Fwd: boolean;
        Parent: boolean;
    };
};
