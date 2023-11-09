/// <reference path="../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component, LegacyRef } from 'react';
//@ts-ignore
import { Dropdown, Color, Size } from '@grapecity/core-ui';
import { ColorEditorLocalization, ColorEditorProps } from './types';
export declare function renderColorMenu(colorValue: Color | null, onSelect: (color: Color) => void, localization: ColorEditorLocalization): JSX.Element;
export declare function renderColorDropdown(colorValue: Color | null, onSelect: (color: Color) => void, localization: ColorEditorLocalization, refCallback: LegacyRef<Dropdown>, size?: Size): JSX.Element;
export declare class ColorEditor extends Component<ColorEditorProps> {
    private _dropdown;
    onSelect: (color: any) => void;
    render(): JSX.Element;
}
