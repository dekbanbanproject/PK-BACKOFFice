export declare class Rect {
    constructor(left: number, top: number, width: number, height: number);
    left: number;
    top: number;
    width: number;
    height: number;
}
export declare type FindOptions = {
    Text: string;
    MatchCase: boolean;
    WholeWord: boolean;
    SearchBackward: boolean;
};
export declare class Range {
    readonly length: number;
    readonly Start: number;
    readonly Length: number;
    constructor(start: number, length: number);
    readonly isEmpty: boolean;
    static readonly Empty: Range;
}
export declare class SearchResult {
    readonly PageIndex: number;
    readonly DisplayText: string;
    readonly ItemArea: Rect;
    readonly ItemIndex: number;
    readonly Range: Range;
    readonly UserData?: any;
    constructor(PageIndex: number, DisplayText: string, ItemArea: Rect, ItemIndex: number, Range: Range, UserData?: any);
    static readonly MakePageStart: (pageIndex: number) => SearchResult;
    static readonly PastEnd: SearchResult;
    static readonly BeforeBegin: SearchResult;
}
export declare type ProgressModel = {
    pageIndex: number;
    pageCount: number | null;
};
export declare type Model = {
    options: FindOptions;
    results: SearchResult[];
    status: 'ready' | 'inprogress' | 'suspended' | 'done' | 'cancelled';
    progress: ProgressModel;
};
export declare type Msg = {
    type: 'PushResult';
    payload: SearchResult;
} | {
    type: 'ResetResults';
} | {
    type: 'Started';
} | {
    type: 'SearchCompleted';
} | {
    type: 'Suspended';
} | {
    type: 'Cancelled';
} | {
    type: 'Progress';
    payload: ProgressModel;
} | {
    type: 'UpdateOption';
    payload: {
        option: 'MatchCase' | 'WholeWord';
        value: boolean;
    };
} | {
    type: 'UpdateText';
    payload: {
        text: string;
    };
};
