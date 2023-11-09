import { CancelableEditorBase } from './CancelableEditorBase';
export declare class ChoiceOptionsEditor extends CancelableEditorBase {
    private _changedValue;
    private _contentElement;
    getEditorControls(): JSX.Element[];
    onApply(): void;
    onCancel(): void;
    setControlsVisibility(isVisible: boolean): void;
    getEditButtonLabel(): string;
    protected isValueDirty(): boolean;
    onDelete(index: any): void;
    onAdd(): void;
    onReorder: (value: any) => void;
    createEditor: (value: {
        displayValue: string;
        exportValue: string;
    }, index: number) => JSX.Element;
}
