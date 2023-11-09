//@ts-ignore
import { Size } from '@grapecity/core-ui';
/// <reference path="../../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { ZoomSettings } from '../../DocumentViewer';
/// <reference path="../../../../../vendor/i18next.d.ts" />
//@ts-ignore
import i18next from 'i18next';
export declare type ZoomControlProps = {
    dropup?: boolean;
    size?: Size;
    zoom: ZoomSettings;
    disabled: boolean;
    onChange?: (zoom: ZoomSettings) => void;
    i18n: i18next.WithT;
};
export declare class ZoomControl extends Component<ZoomControlProps> {
    private onDecButtonClick;
    private onIncButtonClick;
    private onZoomSelect;
    render(): JSX.Element;
}
