export declare function scrollIntoView(element: HTMLElement, spot: {
    left?: number;
    top?: number;
}, skipOverflowHiddenElements?: boolean, parent?: any | null): void;
export declare class GcDebounceScroll {
    state: {
        down: boolean;
        lastScrollTop: number;
    };
    private _debounceScrollHandler;
    private _requestAnimationFrame;
    private _scrollContainer;
    private _cancelUnhandledScrolls;
    private _cancelledScrollValue;
    constructor(scrollContainer: HTMLElement, callback: Function, restoreCancelledScrollCallback?: Function | null | undefined);
    setScrollContainer(scrollContainer: any): void;
    set cancelUnhandledScrolls(cancel: boolean);
    get cancelUnhandledScrolls(): boolean;
    destroy(): void;
}
