import { ControlBase } from './ControlBase';
//@ts-ignore
import { ComboBox } from '@grapecity/core-ui';
export declare class ChoiceControl extends ControlBase {
    _comboBoxRef: ComboBox | null;
    render(): JSX.Element;
    getDisplayValue(fieldValue: string | string[]): string;
}
