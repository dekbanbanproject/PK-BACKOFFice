import { WidgetAnnotation } from "../Annotations/AnnotationTypes";
import { FieldValidationResult, ValidationCallerType } from "./types";
import { FormFieldMapping } from "../ViewerOptions";
export declare function validateField(caller: ValidationCallerType, field: WidgetAnnotation, in17n?: any, mappingSettings?: FormFieldMapping, commonValidator?: (fieldValue: string | string[], field: WidgetAnnotation, args: {
    caller: ValidationCallerType;
}) => boolean | string, ignoreValidationAttrs?: boolean): FieldValidationResult;
export declare function validateAnnotationLayerField(annotationSection: HTMLElement, data: WidgetAnnotation, linkService: any, commonValidator?: (fieldValue: string | string[], field: WidgetAnnotation, args: {
    caller: ValidationCallerType;
}) => boolean | string, silent?: boolean, ignoreValidationAttrs?: boolean, caller?: ValidationCallerType): FieldValidationResult;
