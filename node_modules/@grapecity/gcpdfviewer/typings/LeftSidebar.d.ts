/// <reference path="vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
//@ts-ignore
import { MenuAPI } from '@grapecity/core-ui';
//@ts-ignore
import { Model } from '@grapecity/viewer-core';
//@ts-ignore
import { PanelHandle } from '@grapecity/viewer-core/types/api/PluginModel';
import GcPdfViewer from '.';
import { GcPdfViewerDataLoader, ViewerMetaDataType } from './Core/GcPdfViewerDataLoader';
export declare const commonStateInit: () => any;
export declare class LeftSidebar {
    viewer: GcPdfViewer;
    dataLoader: GcPdfViewerDataLoader;
    private _activePanelId;
    private _layersPanel?;
    private _structureTreePanel?;
    private _isViewerReady;
    constructor(viewer: GcPdfViewer, dataLoader: GcPdfViewerDataLoader);
    get activePanelId(): string | null;
    set activePanelId(id: string | null);
    get in17n(): i18n;
    get menu(): MenuAPI;
//@ts-ignore
    uiInstance: () => import("@grapecity/core-ui/lib/types/utils").PublicAPI;
    hide(): void;
    show(): void;
    addLayersPanel(): PanelHandle;
    addStructureTreePanel(): PanelHandle;
    onPanelDataLoaded(key: ViewerMetaDataType, data: any): void;
    updatePanels(state: Model): void;
}
