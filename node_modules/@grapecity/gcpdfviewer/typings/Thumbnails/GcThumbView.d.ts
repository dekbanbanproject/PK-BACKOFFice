/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare enum RenderingStates {
    INITIAL = 0,
    RUNNING = 1,
    PAUSED = 2,
    FINISHED = 3
}
export declare class GcThumbView {
    div: HTMLDivElement;
    renderingState: RenderingStates;
    renderTask: any;
    resume: any;
    pdfPage: any;
    pdfPageRotate: any;
    rotation: any;
    viewport: any;
    pageWidth: any;
    pageHeight: any;
    pageRatio: number;
    canvasHeight: number;
    canvasWidth: number;
    ring: any;
    scale: number;
    canvas: any;
    image: any;
    private _serviceProvider;
    id: any;
    renderingId: string;
    pageLabel: any;
    linkService: any;
    renderingQueue: any;
    disableCanvasToImageConversion: boolean;
    anchor: HTMLSpanElement | HTMLAnchorElement;
    label: HTMLSpanElement;
    in17n: i18n;
    constructor(pageIndex: number, pageLabel: string, thumbsContainer: HTMLDivElement, in17n: i18n, serviceProvider: any, defaultViewPort: any, disableCanvasToImageConversion?: boolean);
    cancelRendering(): void;
    static cleanup(): void;
    _convertCanvasToImage(): void;
    draw(): Promise<any>;
    get pageId(): string;
    setImage(pageView: any): void;
    setPageLabel(pageLabel?: string | null): void;
    _getPageDrawContext(): CanvasRenderingContext2D | null;
    reset(): void;
    setPdfPage(pdfPage: any): void;
    update(rotation: number): void;
}
