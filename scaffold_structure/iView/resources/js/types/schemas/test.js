import HelloType, { Dict, Enum, Tuple, List, Type, Rule, Self, IfExists } from 'hello-type'

const TestType = new Type({
    variable: String,
});


export default {
    namespace: 'test/test/test',
    assert(data) {
        TestType.assert(data);
    },
};