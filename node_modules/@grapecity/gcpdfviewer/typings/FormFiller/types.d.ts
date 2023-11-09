import { WidgetAnnotation, GcProps, AnnotationTypeCode } from "../Annotations/AnnotationTypes";
import { FormFillerSettings, FormFieldMapping } from "../ViewerOptions";
export declare type FieldValidationResult = {
    valid: boolean;
    invalidLabel?: string;
};
export declare type ValidationCallerType = 'form-filler' | 'annotation-layer' | 'user-code';
export declare type FormFillerDialogModel = {
    enabled: boolean;
    showModal: boolean;
    fields: {
        pageNumber: number;
        annotation: WidgetAnnotation;
    }[] | null;
    isChanged: boolean;
};
export declare type FormFillerDialogProps = {};
export declare type FormFillerModel = {
    fields: (WidgetAnnotation | FieldMappingStub)[];
    dirtyHash: {
        [fieldId: string]: WidgetAnnotation | undefined;
    };
    failedValidationHash: {
        [fieldId: string]: FieldValidationResult | undefined;
    };
    anyFieldChanged: boolean;
};
export declare type FormFillerProps = {
    fields: {
        pageNumber: number;
        annotation: WidgetAnnotation;
    }[];
    enabled: boolean;
    settings?: FormFillerSettings;
    in17n: any;
    onChanged: (dirtyHash: {
        [fieldId: string]: WidgetAnnotation | undefined;
    }) => void;
};
export declare type FieldMappingStub = {
    id: string;
    fieldName: string;
    fieldValue: string;
    fieldType: 'custom-content';
    mapping: FormFieldMapping;
    gcProps: GcProps;
    annotationType: AnnotationTypeCode;
};
