export declare function floor10(value: any, exp: any): any;
export declare function ceil10(value: any, exp: any): any;
export declare function round10(value: number, exp?: number): number;
export declare function normalizeRect(rect: number[]): number[];
export declare function rotatePoint(cx: number, cy: number, x: number, y: number, angle: number, isDeg?: boolean): number[];
export declare function findVectorsAngle(p1: number[], p2: number[], cp: number[]): number;
export declare function rad2Deg(radians: number): number;
export declare function deg2rad(degrees: number): number;
export declare function rotateRect(rect: number[], cxcy: number[] | null, angle: number, isDeg?: boolean): number[];
export declare function reverseRotateRect(transformedRect: number[], cxcy: number[] | null, angleDeg: any): number[];
export declare function getDistance(x1: any, y1: any, x2: any, y2: any): number;
export declare function getAngle(x1: any, y1: any, x2: any, y2: any): number;
export declare function rotateRectAndFillBoth(rect: number[], cxcy: number[], angle: number, isDeg?: boolean): {
    rotatedRect: number[];
    transformedRect: number[];
};
export declare function findLinesIntersection(A: number[], B: number[], C: number[], D: number[]): number[] | null;
