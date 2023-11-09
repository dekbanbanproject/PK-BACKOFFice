//@ts-ignore
import { DropdownItem } from "@grapecity/core-ui";
import { IGcDocumentContextMenuContext } from "./types";
import GcPdfViewer from "..";
import { GcSelectionPoint } from "../Models/GcMeasurementTypes";
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare class GcDocumentContextMenu {
    viewer: GcPdfViewer;
    container: HTMLElement;
    context: IGcDocumentContextMenuContext;
    docViewer: any;
    private _openParams;
    _targetAnnotationId: string;
    in17n: i18n;
    static register(viewer: GcPdfViewer, container: HTMLElement, context: IGcDocumentContextMenuContext, docViewer: any): void;
    static unregister(container: HTMLElement): void;
    mousePosition: GcSelectionPoint;
    insertPosition: GcSelectionPoint;
    private constructor();
    private open;
    private openInternal;
    private close;
    private get contextMenuProvider();
    private getDropdownItems;
    addShowCommentPanel(items: DropdownItem[]): void;
    addPasteAction(items: DropdownItem[]): void;
    addAnnotationMenuItems(items: DropdownItem[], annotationId: string): void;
    addSplitter(items: DropdownItem[]): void;
    addCreateLinkFromSelection(items: DropdownItem[], selectionCopier: any, isOverAnnotation: boolean): void;
    addCopyText(items: DropdownItem[], selectionCopier: any): void;
    addPrint(items: DropdownItem[], selectionCopier: any): void;
    addStickyNote(items: DropdownItem[]): void;
    private _convertFromGlobalPoint;
}
