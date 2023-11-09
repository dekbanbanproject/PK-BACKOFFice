/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
export declare type ConfirmationDialogState = {
    showModal: boolean;
};
export declare type ConfirmationDialogProps = {
    title?: string;
};
export declare class ConfirmationDialog extends Component<ConfirmationDialogProps, ConfirmationDialogState> {
    private _confirmationPromise?;
    private _resolve?;
    private _confirmationText;
    private _level?;
    state: {
        showModal: boolean;
    };
    private _resolveConfirmation;
    confirm(confirmationText?: string | JSX.Element, level?: "info" | "warning" | "error"): Promise<boolean>;
    render(): JSX.Element;
}
