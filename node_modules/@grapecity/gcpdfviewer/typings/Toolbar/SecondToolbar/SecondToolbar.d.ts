//@ts-ignore
import { ToolbarItem } from "@grapecity/viewer-core";
import { GcPdfViewer } from "../..";
import { SecondToolbarControl } from "./SecondToolbarControl";
import { SecondToolbarLayoutMode } from "./types";
export declare class SecondToolbar {
    private _viewer;
    private _layoutMode;
    items: {
        [key: string]: ToolbarItem;
    };
    secondToolbarControl: SecondToolbarControl;
    private _marginTop;
    constructor(_viewer: GcPdfViewer);
    get isShown(): boolean;
    get layoutMode(): SecondToolbarLayoutMode;
    set layoutMode(mode: SecondToolbarLayoutMode);
    get marginTop(): number;
    set marginTop(val: number);
    addItem(toolbarItem: ToolbarItem): void;
    show(mode: SecondToolbarLayoutMode | string): Promise<void>;
    hide(): void;
    raiseStateChanged(): void;
    private static createControl;
}
