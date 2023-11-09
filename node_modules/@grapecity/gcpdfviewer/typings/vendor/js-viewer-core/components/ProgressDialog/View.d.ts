import { Model, Msg as ModelMsg } from "./types";
/// <reference path="../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
/// <reference path="../../../../vendor/i18next.d.ts" />
//@ts-ignore
import i18next from 'i18next';
declare type Props = {
    dispatch: (cmd: ModelMsg) => void;
    onCancel: () => void;
    i18n: i18next.WithT;
};
export declare class View extends Component<Props, Model> {
    constructor();
    onCancel: (_: any) => void;
    render(): JSX.Element | null;
}
export {};
