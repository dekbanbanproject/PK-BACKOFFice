import { CancelableEditorBase } from './CancelableEditorBase';
export declare class TextAreaEditor extends CancelableEditorBase {
    private _textArea;
    getEditorControls(): JSX.Element[];
    onApply(): void;
    onCancel(): void;
    setControlsVisibility(isVisible: boolean): void;
    componentDidUpdate(): void;
    protected isValueDirty(): boolean;
    private _onTextAreaInput;
    private _onChange;
    private get updatedTextAreaValue();
    private set updatedTextAreaValue(value);
}
