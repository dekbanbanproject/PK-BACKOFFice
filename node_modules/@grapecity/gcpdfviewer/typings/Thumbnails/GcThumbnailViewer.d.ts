import { GcThumbView } from './GcThumbView';
import { GcDebounceScroll } from '../Utils/ScrollUtils';
import PdfReportPlugin from '../plugin';
import { GcRangedThumbsPane } from './GcRangedThumbsPane';
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare class GcThumbnailViewer {
    private _plugin;
    private _mountedToDocViewer;
    private _currentPageNumber;
    private _pdfDocument;
    private _thumbsContainer;
    private _serviceProvider;
    private _thumbnails;
    _pagesRotation: number;
    _pageLabels: any;
    _pagesRequests: any[];
    container: HTMLDivElement;
    linkService: any;
    renderingQueue: any;
    scroll: GcDebounceScroll;
    _thumbsScrollContainer: HTMLDivElement;
    _demandPanes: GcRangedThumbsPane[];
    _demandPaneIndex: number;
    in17n: i18n;
    constructor(thumbsContainer: HTMLDivElement, _plugin: PdfReportPlugin);
    _ensureScrollUpdatedBound(): void;
    get mounted(): boolean;
    set mounted(val: boolean);
    get thumbsScrollContainer(): HTMLDivElement;
    get isVisible(): boolean;
    cleanup(): void;
    _ensurePdfPageLoaded(thumbView: GcThumbView): any;
    get pagesRotation(): number;
    set pagesRotation(val: number);
    setPageLabels(labels: any): void;
    forceRendering(): boolean;
    reset(): any;
    setDocument(pdfDocument: any, forceRendering?: boolean, pageNumber?: number): any;
    scrollThumbnailIntoView(pageNumber: number): void;
    _cancelRendering(): void;
    getThumbnail(index: any): GcThumbView;
    _scrollUpdated(): void;
    private _getVisibleThumbs;
}
