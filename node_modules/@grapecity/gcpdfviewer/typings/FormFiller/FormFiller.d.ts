/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { WidgetAnnotation, ButtonWidget } from "../Annotations/AnnotationTypes";
import { FormFillerProps, FormFillerModel, FieldMappingStub } from './types';
import { FormFieldMapping } from '../ViewerOptions';
export declare class FormFiller extends Component<FormFillerProps, FormFillerModel> {
    private _mounted;
    private _outerElement;
    private _pendingInvalidFocus;
    private _autofocusFieldId?;
    private _pendingAutofocus;
    constructor(props: FormFillerProps, state: any);
    componentDidMount(): void;
    componentWillUnmount(): void;
    componentDidUpdate(): void;
    showValidationMessages(): void;
    render(): JSX.Element;
    readInitialFieldsState(): FormFillerModel;
    createFieldStub(fieldName: string, mapping: FormFieldMapping): FieldMappingStub;
    reset(): void;
    get isMounted(): boolean;
    raiseOnInitialize(): void;
    getFieldByName(fieldName: string): (WidgetAnnotation | FieldMappingStub) | undefined;
    onFieldChanged(changedField: WidgetAnnotation): void;
    onBeforeRadioButtonChange(radioButton: ButtonWidget, changedFields: WidgetAnnotation[]): boolean;
    validateFields(): boolean;
    autoFocus(): void;
    private _autoFocus;
    private _focusInvalid;
    private _focusFieldControl;
}
