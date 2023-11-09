import PdfReportPlugin from "../plugin";
import { ReplyTool } from "../ReplyTool/ReplyTool";
import { ReplyToolModel } from './../ReplyTool/types';
//@ts-ignore
import { Store } from "@grapecity/viewer-core";
import { GcRightSidebarTool, GcRightSidebarState } from "./types";
export declare class GcRightSidebar {
    plugin: PdfReportPlugin;
    private _commentsStore;
    private _sidebarElement;
    private _commentApp;
    private _activeTool;
    private _activeState;
    private _sidebarToggle;
    constructor(plugin: PdfReportPlugin);
    show(sidebarState?: GcRightSidebarState, expandedTool?: GcRightSidebarTool | undefined): void;
    hide(): void;
    collapse(): void;
    expand(): void;
    toggle(): void;
    get activeTool(): GcRightSidebarTool;
    get activeState(): GcRightSidebarState;
    get replyTool(): ReplyTool | null;
    get hasReplyTool(): boolean;
    get commentsStore(): Store<any, any> | null;
    addReplyTool(sidebarState?: GcRightSidebarState): void;
    dispatchCommentsState(state?: ReplyToolModel): void;
    private _updateUI;
    private _createControls;
}
