/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { Icon, PlainTextEditorLocalization, PropertyEditorProps } from '@grapecity/core-ui';
export declare type NullableIntEditorProps = PropertyEditorProps & PlainTextEditorLocalization & {
    id?: string;
    name?: string;
    minValue?: number;
    maxValue?: number;
    iconDecrease?: Icon;
    iconIncrease?: Icon;
    step?: number;
    displayValue?: string;
    placeholder?: string;
    validate?: (value: string) => boolean;
};
declare type NullableIntEditorState = {
    value: any;
};
export declare class NullableIntEditor extends Component<NullableIntEditorProps, NullableIntEditorState> {
    constructor(props: NullableIntEditorProps, context?: any);
    private _outer;
    private _currentStep;
    static defaultProps: Partial<NullableIntEditorProps>;
    componentDidMount(): void;
    componentWillUnmount(): void;
    private _onWindowMouseUp;
    private validateNumber;
    private onChange;
    private _intTimer;
    private _startIncTimer;
    getPropValue(defaultValue?: number | ""): number | "";
    private _clearIncTimer;
    private _onIncDecMouseDown;
    private onNumberChange;
    render(): JSX.Element;
}
export {};
