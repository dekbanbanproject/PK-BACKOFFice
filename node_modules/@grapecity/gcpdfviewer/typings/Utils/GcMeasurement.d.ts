import { IGcRect, IGcPoint } from "../Models/GcMeasurementTypes";
export declare class GcMeasurement {
    static fitWithAspectRatio(sourceSize: {
        width: number;
        height: number;
    }, fitSize: {
        width: number;
        height: number;
    }): {
        width: number;
        height: number;
    };
    static measureDomText(text: string, fontFamily: string, fontSize: string): {
        w: number;
        h: number;
    };
    static getElementOuterSize(element: HTMLElement): {
        w: number;
        h: number;
    };
    static getElementInnerSize(element: HTMLElement): {
        w: number;
        h: number;
    };
    static intersectRect(r1: IGcRect, r2: IGcRect): boolean;
    static isPointInBounds(p: IGcPoint, bb: IGcRect): boolean;
    static getWindowScrollOffsets(): {
        left: number;
        top: number;
    };
    static getWindowSize(): {
        w: number;
        h: number;
    };
    static getAbsoluteOffsetLeft(elem: HTMLElement | null | undefined): number;
    static getAbsoluteOffsetTop(elem: HTMLElement | null | undefined): number;
}
