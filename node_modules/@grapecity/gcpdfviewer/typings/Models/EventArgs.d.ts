import GcPdfViewer from "..";
import { AnnotationBase } from "../Annotations/AnnotationTypes";
import { OpenParameters } from "./ViewerTypes";
export declare type EventName = "ErrorEvent" | "BeforeOpenEvent" | "AfterOpenEvent" | "ThemeChangedEvent" | "BeforeAddAnnotation" | "AfterAddAnnotation" | "BeforeUpdateAnnotation" | "AfterUpdateAnnotation" | "BeforeRemoveAnnotation" | "AfterRemoveAnnotation";
export declare type EventArgs = {
    eventName?: EventName;
    source?: GcPdfViewer;
};
export declare type CancelEventArgs = {
    cancel?: boolean;
} & EventArgs;
export declare type ErrorEventArgs = {
    readonly message: string;
    readonly type: 'open' | string;
    readonly exception?: any;
} & EventArgs;
export declare type BeforeOpenEventArgs = {
    readonly payload: Uint8Array | string;
    readonly openParameters?: OpenParameters;
    readonly type: 'binary' | 'url';
} & EventArgs;
export declare type AfterOpenEventArgs = {} & EventArgs;
export declare type ThemeChangedEventArgs = {
    readonly theme: string;
} & EventArgs;
export declare type BaseAnnotationEventArgs = {
    pageIndex: number;
    annotation: AnnotationBase;
} & EventArgs;
export declare type BaseAnnotationCancelEventArgs = {} & BaseAnnotationEventArgs & CancelEventArgs;
export declare type BeforeAddAnnotationEventArgs = {
    annotationElement?: HTMLElement;
} & BaseAnnotationCancelEventArgs;
export declare type AfterAddAnnotationEventArgs = {} & BaseAnnotationEventArgs;
export declare type BeforeUpdateAnnotationEventArgs = {} & BaseAnnotationCancelEventArgs;
export declare type AfterUpdateAnnotationEventArgs = {} & BaseAnnotationEventArgs;
export declare type BeforeRemoveAnnotationEventArgs = {} & BaseAnnotationCancelEventArgs;
export declare type AfterRemoveAnnotationEventArgs = {} & BaseAnnotationEventArgs;
