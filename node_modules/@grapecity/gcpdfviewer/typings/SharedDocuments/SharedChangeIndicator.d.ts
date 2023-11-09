import { AnnotationBase } from "../Annotations/AnnotationTypes";
export declare function getUserColor(userName: string, presenceColors: {
    [userName: string]: string;
}): string;
export declare function createSharedChangeIndicators(container: HTMLElement, data: AnnotationBase, docViewer: any): void;
