/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { WidgetAnnotation } from '../Annotations/AnnotationTypes';
import { FieldValidationResult, FieldMappingStub } from './types';
import { FormFieldMapping } from '../ViewerOptions';
/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare type FieldRowProps = {
    field: WidgetAnnotation | FieldMappingStub;
    isFieldDirty: boolean;
    validationResult?: FieldValidationResult;
    gcProps?: FormFieldMapping;
    commonValidator?: (fieldValue: string | string[], field: WidgetAnnotation, args: {
        caller: 'form-filler' | 'annotation-layer';
    }) => boolean | string;
    enabled: boolean;
    in17n: i18n;
    onChanged: (field: WidgetAnnotation) => void;
};
export declare class FieldRow extends Component<FieldRowProps, any> {
    render(): JSX.Element;
    renderFieldControl(title: string, nolabel: boolean, displayNameElements: JSX.Element[]): JSX.Element | JSX.Element[] | null;
    getFieldTitle(field: WidgetAnnotation, mapping?: FormFieldMapping): string;
    getFieldDisplayName(field: WidgetAnnotation, mapping?: FormFieldMapping): string;
}
