import { LineEndStyle } from "../Annotations/AnnotationTypes";
export declare class AnnotationsSvgFactory {
    private static _instance;
    static instance(): AnnotationsSvgFactory;
    private constructor();
    create(width: number, height: number, attributes?: any, innerHtml?: string | null): SVGElement;
    createElement(type: string): SVGElement;
    createSvgRect(params: {
        x: number;
        y: number;
        w: number;
        h: number;
        stroke?: string;
        strokeWidth?: number;
        fill?: string;
        strokeDashArray?: string;
    }): SVGElement;
    getLineAppearance(view: number[], rect: number[], lineCoordinates: number[], strokeColor: string, fillColor: string, strokeWidth: number, dashArray?: number[] | null, lineStart?: LineEndStyle, lineEnd?: LineEndStyle, callerId?: string): SVGElement;
    createSvgLine(svg: any, view: number[], rect: number[], lineCoordinates: number[], strokeColor: string, fillColor: string, strokeWidth: number, dashArray?: number[] | null, lineStart?: LineEndStyle, lineEnd?: LineEndStyle, callerId?: string): SVGElement;
    getLineEndGraphics(lineEnd: LineEndStyle, params: {
        strokeColor?: string;
        fillColor?: string;
        caller?: string;
    }): {
        markerId: string;
        markerGraphics: string;
    };
    generateSvgLineHtml(x1: number, y1: number, x2: number, y2: number, lineWidth: number, dashArray: number[] | null, strokeColor: string, markerId?: string): string;
}
