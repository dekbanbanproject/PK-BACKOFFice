import { GcThumbView } from "./GcThumbView";
export declare const DEMAND_PANE_THUMBS_COUNT: number;
export declare const DEMAND_PANE_INDEX_ATTR = "data-pane-index";
export declare class GcRangedThumbsPane {
    id: number;
    div: HTMLDivElement;
    private paneInner;
    constructor(id: number, parentContainer: HTMLElement);
    get isVisible(): boolean;
    ensureVisible(demandPanes: GcRangedThumbsPane[], prevDemandPaneIndex?: number): any;
    updateHeight(thumbsCount: number): void;
    get thumbsHolder(): HTMLDivElement;
    static ensureDemandPaneVisible(thumbnailView: GcThumbView, demandPanes: GcRangedThumbsPane[], currentPaneIndex: number): number;
    static hidePane(ind: number, demandPanes: GcRangedThumbsPane[]): any;
    static showPane(ind: number, demandPanes: GcRangedThumbsPane[]): any;
    static getPaneIndex(thumbnailView: GcThumbView): number;
}
