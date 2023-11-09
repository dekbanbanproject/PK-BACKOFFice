import { PluginModel, ReportViewerCmd } from '../../api';
export declare type PageInfo = {
    size: PluginModel.PageSize | null;
    data: PluginModel.PageView | null;
    isLoading: boolean;
    isInvalid: boolean;
    reqIndex: number;
};
export declare enum ViewerStatus {
    Empty = 0,
    Loading = 1,
    Cancelled = 2,
    Ready = 3,
    Failed = 4
}
export declare enum ZoomMode {
    Value = 0,
    PageWidth = 1,
    WholePage = 2
}
export declare enum ViewMode {
    SinglePage = 0,
    Continuous = 1
}
export declare enum MouseMode {
    Select = 0,
    Move = 1
}
export declare type ZoomSettings = {
    mode: ZoomMode.PageWidth;
} | {
    mode: ZoomMode.WholePage;
} | {
    mode: ZoomMode.Value;
    factor: number;
};
export declare type ViewSettings = {
    zoom: ZoomSettings;
    mode: ViewMode;
    mouseMode: MouseMode;
    isFullscreen: boolean;
    narrowScreen: boolean;
    isToolbarVisible: boolean;
    isSidebarVisible: boolean;
};
export declare type DocViewModel = {
    readonly status: ViewerStatus;
    readonly pageCount: number;
    readonly pageIndex: number;
    readonly scrollRequestNo: number;
    readonly scrollToSelector: string | null;
    readonly progressMessage: string;
    readonly pageBuffer: Array<PageInfo>;
    readonly highlightPage: {
        pageIndex: number;
        pageView: PluginModel.PageView;
    } | null;
};
export declare type SettingsMsg = {
    type: "UpdateZoom";
    payload: {
        zoom: ZoomSettings;
    };
} | {
    type: "UpdateView";
    payload: {
        mode: ViewMode;
    };
} | {
    type: "UpdateMouseMode";
    payload: {
        mode: MouseMode;
    };
} | {
    type: "UpdateFullscreen";
    payload: {
        isFullscreen: boolean;
    };
} | {
    type: "UpdateToolbarVisibility";
    payload: {
        isVisible: boolean;
    };
} | {
    type: "UpdateSidebarVisibility";
    payload: {
        isVisible: boolean;
    };
} | {
    type: "ToggleNarrowScreen";
    payload: {
        isNarrow?: boolean;
    };
};
export declare type DocViewMsg = {
    type: "Reset";
    payload: {};
} | {
    type: "UpdateStatus";
    payload: {
        isLoading: boolean;
        pageCount: number;
        message: string;
    };
} | {
    type: "Cancelled";
    payload: {
        message: string;
    };
} | {
    type: "Failed";
    payload: {
        message: string;
    };
} | {
    type: "SetCurrentPage";
    payload: {
        pageIndex: number;
        doScroll?: boolean;
        scrollTo?: string;
    };
} | {
    type: "Fetching";
    payload: {
        pageIndex: number;
        reqIndex: number;
    };
} | {
    type: "Fetched";
    payload: {
        pageIndex: number;
        reqIndex: number;
        data: PluginModel.PageView;
        size: PluginModel.PageSize;
    };
} | {
    type: "RemoveHighlight";
} | {
    type: "HighlightPage";
    payload: {
        pageIndex: number;
        pageView: PluginModel.PageView;
    };
} | {
    type: "Invalidate";
    payload: {
        startPage: number;
        pageCount: number;
    };
};
export declare type PageViewProps = DocViewModel & {
    settings: ViewSettings;
    dispatchVwr: (cmd: ReportViewerCmd) => void;
//@ts-ignore
//@ts-ignore
    onClick: JSX.EventHandler<MouseEvent>;
};
export declare type Model = {
    settings: ViewSettings;
    session: DocViewModel;
};
export declare type Msg = {
    type: 'settings';
    payload: SettingsMsg;
} | {
    type: 'session';
    payload: DocViewMsg;
};
