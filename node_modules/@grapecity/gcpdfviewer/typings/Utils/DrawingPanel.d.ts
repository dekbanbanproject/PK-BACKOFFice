export declare class DrawingPanel {
    canvas: HTMLCanvasElement;
    ctx: CanvasRenderingContext2D;
    constructor(canvasSelector: string | HTMLCanvasElement);
    clearCanvas(): void;
    _touchStart(e: any): void;
    touchMove(e: any): void;
    mouseDown(): void;
    mouseMove(e: any): void;
    mouseUp(): void;
}
