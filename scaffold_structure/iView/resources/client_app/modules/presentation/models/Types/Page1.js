import HelloType, { Dict, Enum, Tuple, List, Type, Rule, Self, IfExists } from 'hello-type'

const Page1 = new Type({
    variable: String,
});

export default {
    namespace: 'models/DTOs/Page1',
    assert(data) {
        Page1.assert(data);
    },
};