/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { FieldRowProps } from '../FieldRow';
import { GcProps, WidgetAnnotation } from '../../Annotations/AnnotationTypes';
import { FormFieldMapping } from '../../ViewerOptions';
export declare type ControlBaseProps = {
    gcProps: GcProps;
    title: string;
} & FieldRowProps;
export declare type CommonInputProperties = {
    key: string;
    name: string;
    disabled: boolean;
    inputmode?: string;
    placeholder: string;
    value: any;
    title: string;
    autocomplete?: string;
    readOnly?: boolean;
    pattern?: string;
    min?: any;
    max?: any;
    maxLength?: number;
    minLength?: number;
    spellCheck?: 'true' | 'false';
};
export declare class ControlBase extends Component<ControlBaseProps, any> {
    onFieldValueChange(newValue: string | string[]): void;
    getCommonProps(): CommonInputProperties;
    getPlaceholder(field: WidgetAnnotation, mappingSettings?: FormFieldMapping): string;
}
