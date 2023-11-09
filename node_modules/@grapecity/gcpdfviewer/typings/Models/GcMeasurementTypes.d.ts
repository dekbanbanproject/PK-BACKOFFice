export interface IGcPoint {
    x: number;
    y: number;
}
export interface IGcRect extends IGcPoint {
    w: number;
    h: number;
}
export declare class GcSelectionPoint implements IGcPoint {
    constructor(x: number, y: number, pageIndex: number);
    x: number;
    y: number;
    pageIndex: number;
}
