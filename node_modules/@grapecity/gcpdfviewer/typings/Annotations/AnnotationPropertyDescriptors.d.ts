//@ts-ignore
import { PropertyDescriptor, FriendlyEnum } from "@grapecity/core-ui";
import { AnnotationTypeCode, AnnotationBase } from "./AnnotationTypes";
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
import { ViewerOptions } from "../ViewerOptions";
export declare let imageAngleDescriptor: PropertyDescriptor;
export declare let rotateDescriptor: PropertyDescriptor;
export declare let rotateWithOrientationHintDescriptor: PropertyDescriptor;
export declare let boundsWidthDescriptor: PropertyDescriptor;
export declare let boundsHeightDescriptor: PropertyDescriptor;
export declare let hasEditFlagDescriptor: PropertyDescriptor;
export declare let fileNameDescriptor: PropertyDescriptor;
export declare let fileContentDescriptor: PropertyDescriptor;
export declare let soundContentDescriptor: PropertyDescriptor;
export declare let soundBytesDescriptor: PropertyDescriptor;
export declare let imageFileContentDescriptor: PropertyDescriptor;
export declare let actionPropertyDescriptor: PropertyDescriptor;
export declare function initializePropertyDescriptors(in17n: i18n): void;
export declare let textIconShapeEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let attachmentIconShapeEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let soundIconShapeEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let lineEndingEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let limitedBorderStyleTypeEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let linkTypeEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let destTypeEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let namedActionEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let fontNameEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let onOffEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let borderStyleTypeEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let orientationEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let textAlignEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let annotationStateEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare let annotationStateModelEnumProps: {
    friendlyEnum: FriendlyEnum;
};
export declare function initializeEnumProps(in17n: i18n, options: ViewerOptions): void;
export declare function getDefaultAnnotationObject(type: AnnotationTypeCode): any;
export declare function getAnnotationDescriptors(annotation: AnnotationBase): PropertyDescriptor[];
