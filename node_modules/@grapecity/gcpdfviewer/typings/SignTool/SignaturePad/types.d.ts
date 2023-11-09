export interface BasicPoint {
    x: number;
    y: number;
    time: number;
}
export declare class Point implements BasicPoint {
    x: number;
    y: number;
    time: number;
    constructor(x: number, y: number, time?: number);
    distanceTo(start: BasicPoint): number;
    equals(other: BasicPoint): boolean;
    velocityFrom(start: BasicPoint): number;
}
export declare class Bezier {
    startPoint: Point;
    control2: BasicPoint;
    control1: BasicPoint;
    endPoint: Point;
    startWidth: number;
    endWidth: number;
    static fromPoints(points: Point[], widths: {
        start: number;
        end: number;
    }): Bezier;
    private static calculateControlPoints;
    constructor(startPoint: Point, control2: BasicPoint, control1: BasicPoint, endPoint: Point, startWidth: number, endWidth: number);
    length(): number;
    private point;
}
