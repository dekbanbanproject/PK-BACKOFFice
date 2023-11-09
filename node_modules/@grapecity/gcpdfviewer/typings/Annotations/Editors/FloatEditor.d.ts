/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { Icon, PlainTextEditorLocalization, PropertyEditorProps } from '@grapecity/core-ui';
export declare type FloatEditorProps = PropertyEditorProps & PlainTextEditorLocalization & {
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
    roundPrecision?: number;
};
declare type FloatEditorState = {
    value: number;
};
export declare class FloatEditor extends Component<FloatEditorProps, FloatEditorState> {
    static numberRegex: RegExp;
    private _outer;
    private _currentStep;
    private _intTimer;
    constructor(props: FloatEditorProps, context?: any);
    static defaultProps: Partial<FloatEditorProps>;
    get roundPrecision(): number;
    componentDidMount(): void;
    componentWillUnmount(): void;
    private _onWindowMouseUp;
    private roundValue;
    private validateNumber;
    private onChange;
    private _startIncTimer;
    private _clearIncTimer;
    private _onIncDecMouseDown;
    ensureMinMax(result: number): number;
    private onNumberChange;
    render(): JSX.Element;
}
export {};
